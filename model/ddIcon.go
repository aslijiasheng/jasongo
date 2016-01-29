package model

type DdIcon struct {
	IconId   int    `json:"icon_id" xorm:"not null pk autoincr INT(11)"`
	IconName string `json:"icon_name" xorm:"not null VARCHAR(128)"`
	IconCode string `json:"icon_code" xorm:"not null VARCHAR(128)"`
}
