package email

import (
	"net/mail"
)

var Config = Configuration{
	Port: 587,
}

type Configuration struct {
	Host     string
	Port     uint16
	Username string
	Password string
	From     mail.Address
}
