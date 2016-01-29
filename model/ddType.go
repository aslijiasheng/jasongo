package model

type DdType struct {
	TypeId    int    `json:"type_id" xorm:"not null pk autoincr INT(11)"`
	TypeName  string `json:"type_name" xorm:"CHAR(255)"`
	ObjId     int    `json:"obj_id" xorm:"INT(11)"`
	TypeLabel string `json:"type_label" xorm:"not null VARCHAR(128)"`
}
