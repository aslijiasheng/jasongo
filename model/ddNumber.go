package model

type DdNumber struct {
	OrderType   int    `json:"order_type" xorm:"not null pk INT(3)"`
	OrderNumber string `json:"order_number" xorm:"VARCHAR(128)"`
}
