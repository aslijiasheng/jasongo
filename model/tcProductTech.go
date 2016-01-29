package model

type TcProductTech struct {
	ProductTechId     int    `json:"product_tech_id" xorm:"not null pk autoincr INT(11)"`
	ProductTechCode   string `json:"product_tech_code" xorm:"VARCHAR(128)"`
	ProductTechName   string `json:"product_tech_name" xorm:"VARCHAR(128)"`
	ProductTechDesc   string `json:"product_tech_desc" xorm:"VARCHAR(225)"`
	ProductTechU8Code string `json:"product_tech_u8_code" xorm:"VARCHAR(128)"`
}
