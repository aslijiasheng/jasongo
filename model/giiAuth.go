package model

type GiiAuth struct {
	AuId       int    `json:"au_id" xorm:"not null pk autoincr INT(11)"`
	AuName     string `json:"au_name" xorm:"not null VARCHAR(30)"`
	AuPassword string `json:"au_password" xorm:"not null CHAR(50)"`
}
