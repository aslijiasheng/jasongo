package gLoggerService

import (
	"code.google.com/p/log4go"
	"time"
)

type LoggerStruct struct {
	FileHandle string //执行写入的文件名
	ErrMsg     string //写入的内容
	Count      int    //数据大小
	Level      string //写入级别
}

var gLogger log4go.Logger
var logger LoggerStruct

/***************************************************************************************************
    Functions
***************************************************************************************************/
//do init before all others
func initAll() {
	gLogger = nil
	initLogger()
}

//de-init for all
func deinitAll() {
	if nil == gLogger {
		gLogger.Close()
		gLogger = nil
	}
}

//init for logger
func initLogger() {
	gLogger = make(log4go.Logger)
	gLogger.LoadConfiguration("/conf/example.xml")
	gLogger.Info("Current time is : %s", time.Now().Format("15:04:05 MST 2006/01/02"))
	return
}

func LogInit(loggerStruct LoggerStruct) {
	initAll()
	logger = loggerStruct
	level := loggerStruct.Level
	switch level {
	case "info", "INFO":
		logInfo()
	case "debug", "DEBUG":
		logDebug()
	case "warning", "WARNING":
		logWarning()
	case "error", "ERROR":
		logError()
	}
	deinitAll()
	time.Sleep(100 * time.Millisecond)
}

func logInfo() {
	gLogger.Info(logger.FileHandle)
	gLogger.Info(logger.ErrMsg)
	gLogger.Info(logger.Count)
}

func logDebug() {
	gLogger.Debug(logger.FileHandle)
	gLogger.Debug(logger.ErrMsg)
	gLogger.Debug(logger.Count)
}

func logWarning() {
	gLogger.Warn(logger.FileHandle)
	gLogger.Warn(logger.ErrMsg)
	gLogger.Warn(logger.Count)
}

func logError() {
	gLogger.Error(logger.FileHandle)
	gLogger.Error(logger.ErrMsg)
	gLogger.Error(logger.Count)
}
