package model

type Demo struct {
	OrderNumber string `json:"order_number" xorm:"not null pk VARCHAR(255)"`
	BookNo      string `json:"book_no" xorm:"VARCHAR(255)"`
	Tttt        int    `json:"tttt" xorm:"not null default 0 INT(5)"`
	Ttttd       int    `json:"ttttd" xorm:"not null default 0 INT(5)"`
}
