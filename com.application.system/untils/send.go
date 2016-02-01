package main

import (
	"./mail"
	"fmt"
)

func main() {
	email.Config.Host = "smtp.163.com"
	email.Config.Port = 25
	email.Config.From.Name = "监控中心"
	email.Config.From.Address = "aslijiasheng@163.com"
	email.Config.Username = "aslijiasheng@163.com"
	email.Config.Password = "as6938870"

	mail := email.NewBriefMessage("No too much to say", "Hello Email World!!!", "963781990@qq.com")
	err := mail.Send()

	if err != nil {
		fmt.Println(err)
	}
}
