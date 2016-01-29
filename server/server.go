package main

import (
	"time"

	"../com.application.system/config"
	"../model"
	// "../com.application.system/log"
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
)

// API Handler
/**
 *  快递用户接口GET
 */
func expressListUsers(c *echo.Context) error {
	var users []model.TcUser
	err := engine.Find(&users)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, "StatusInternalServerError")
	}
	return c.JSON(http.StatusOK, users)
}

/**
 * 快递查询用户接口POST
 */
func expressQueryUsers(c *echo.Context) error {
	return c.JSON(http.StatusOK, "expressQueryUser")
}

/**
 *  快递通知接口
 */
func expressEmailMessage(c *echo.Context) error {
	userID := c.Form("userID")         //收件人ID
	takeUserID := c.Form("takeUserID") //取件人ID
	return c.JSON(http.StatusOK, tcUser)
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
		return c.JSON(http.StatusOK, "expresstake is failed")
	}
	return c.JSON(http.StatusOK, "expresstake is success")
}

func main() {

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
