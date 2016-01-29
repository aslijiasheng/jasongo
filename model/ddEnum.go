package model

type DdEnum struct {
	EnumId     int    `json:"enum_id" xorm:"not null pk autoincr INT(11)"`
	AttrId     int    `json:"attr_id" xorm:"INT(11)"`
	EnumName   string `json:"enum_name" xorm:"CHAR(255)"`
	EnumKey    string `json:"enum_key" xorm:"VARCHAR(255)"`
	DispOrder  int    `json:"disp_order" xorm:"default 0 INT(11)"`
	IsDefault  int    `json:"is_default" xorm:"default 1 TINYINT(4)"`
	SystemFlag int    `json:"system_flag" xorm:"default 0 TINYINT(4)"`
}
