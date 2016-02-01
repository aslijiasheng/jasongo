package main

import (
	"time"

	"../com.application.system/config"
	"../com.application.system/log"
	"../com.application.system/untils/mail"
	"../model"
	"log"
	"net/http"

	_ "github.com/go-sql-driver/mysql"
	"github.com/go-xorm/xorm"

	"github.com/labstack/echo"
	mw "github.com/labstack/echo/middleware"
)

var (
	//初始化xorm引擎
	engine *xorm.Engine

	err           error
	tcUser        model.TcUser
	tcExpressMail model.TcExpressMail
	tcExpressTake model.TcExpressTake
	loggerService gLoggerService.LoggerStruct
)

const (
	expressTodo            = iota //待发
	expressSucc                   //发送成功
	expressFaild                  //发送失败
	expressRecSend                //重发
	expressQueryUserFailed        //用户查询失败
	expressUserNameEmpty          //用户不能为空
	expressUserPhoneEmpty         //用户手机不能为空
	expressMailFailed             //用户邮件发送失败
	expressMailSucc               //用户邮件发送成功
	expressTakeFailed             //用户取件更新失败
	expressTakeSucc               //用户取件更新成功
)

type errorMsg struct {
	errorNo  int    `json:"errorNo"`
	errorMsg string `json:"errorMsg"`
}

// API Handler
/**
 *  快递用户接口GET
 */
func expressListUsers(c *echo.Context) error {
	var users []model.TcUser
	err := engine.Find(&users)
	if err != nil {
		return c.JSON(http.StatusOK, errorMsg{errorNo: expressQueryUserFailed, errorMsg: "expressQueryUserFailed"})
	}
	return c.JSON(http.StatusOK, users)
}

/**
 * 快递查询用户接口POST
 */
func expressQueryUsers(c *echo.Context) error {
	userName := c.Form("userName")
	userPhone := c.Form("userPhone")

	if userName == "" {
		return c.JSON(http.StatusOK, errorMsg{errorNo: expressUserNameEmpty, errorMsg: "expressUserNameEmpty"})
	}

	if userPhone == "" {
		return c.JSON(http.StatusOK, errorMsg{errorNo: expressUserPhoneEmpty, errorMsg: "expressUserPhoneEmpty"})
	}

	engine.Where("user_name = ? and user_phone = ?", userName, userPhone).AllCols().Get(&tcUser)

	return c.JSON(http.StatusOK, tcUser)
}

/**
 *  快递通知接口
 */
func expressEmailMessage(c *echo.Context) error {
	userID := c.Form("userID")         //收件人ID
	takeUserID := c.Form("takeUserID") //取件人ID 通知ID
	var takeUser model.TcUser
	engine.Id(userID).Get(&takeUser) //通过取件人ID获取取件人信息
	go sendMail(takeUser)
	//留给send mail
	expressMail := &model.TcExpressMail{
		ExpressFromUserId:    userID,
		ExpressToUserId:      takeUserID,
		ExpressCreateUserId:  userID,
		ExpressCreateDate:    time.Now(),
		ExpressToExpressDate: time.Now(),
		ExpressTakeStatus:    expressSucc,
	}
	_, err = engine.Insert(expressMail)
	if err != nil {
		return c.JSON(http.StatusOK, errorMsg{errorNo: expressMailFailed, errorMsg: "expressMailFailed"})
	}
	return c.JSON(http.StatusOK, errorMsg{errorNo: expressMailSucc, errorMsg: "expressMailSucc"})
}

func sendMail(takeUser model.TcUser) {

	email.Config.Host = "smtp.163.com"
	email.Config.Port = 25
	email.Config.From.Name = "门卫"
	email.Config.From.Address = "aslijiasheng@163.com"
	email.Config.Username = "aslijiasheng@163.com"
	email.Config.Password = "as6938870"

	mail := email.NewBriefMessage("您有一封快递到达!", "您有一封快递到达一号门", takeUser.UserEmail)
	err := mail.Send()
	if err != nil {
		log.Fatal(err)
	}
}

/**
 *  快递取件接口
 */
func expressTake(c *echo.Context) error {
	expressID := c.Form("expressID") //取件ID
	userID := c.Form("userID")       //取件人ID
	upUsers := &model.TcExpressTake{
		ExpressTakeUserId: userID,
		ExpressTakeDate:   time.Now(),
	}
	_, err := engine.Where("express_take_express_id = ?", expressID).Cols("express_take_user_id", "express_take_date").Update(upUsers)
	if err != nil {
		return c.JSON(http.StatusOK, errorMsg{errorNo: expressTakeFailed, errorMsg: "expressTakeFailed"})
	}
	return c.JSON(http.StatusOK, errorMsg{errorNo: expressTakeSucc, errorMsg: "expressTakeSucc"})
}

func main() {

	loggerService.FileHandle = "server.go"
	loggerService.ErrMsg = string("init server.go")
	loggerService.Count = len("init server.go")
	loggerService.Level = "DEBUG"
	gLoggerService.LogInit(loggerService)
	go putEnginePoll()
	// Echo instance
	e := echo.New()

	// Debug mode
	e.Debug()

	// Middleware
	e.Use(mw.Logger())
	e.Use(mw.Recover())
	e.Use(mw.Gzip())

	// Routes
	e.Get("/expressListUsers", expressListUsers)
	e.Post("/expressEmailMessage", expressEmailMessage)
	e.Post("/expressQueryUsers", expressQueryUsers)
	e.Post("/expressTake", expressTake)

	loggerService.FileHandle = "server.go"
	loggerService.ErrMsg = string("1323 succ server.go")
	loggerService.Count = len("1323 succ server.go")
	loggerService.Level = "DEBUG"
	gLoggerService.LogInit(loggerService)
	// Start server
	e.Run(":1323")
}

func putEnginePoll() {
	connString := config.Dsn
	engine, err = xorm.NewEngine("mysql", connString)
	if config.Debug {
		engine.ShowSQL = true   //则会在控制台打印出生成的SQL语句；
		engine.ShowDebug = true //则会在控制台打印调试信息；
		engine.ShowWarn = true  //则会在控制台打印警告信息；
	}
	engine.SetMaxIdleConns(1000)
	engine.SetMaxOpenConns(2000)
	if err != nil {
		log.Fatal(err)
	}
}
