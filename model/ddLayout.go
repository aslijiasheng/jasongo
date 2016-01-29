package model

type DdLayout struct {
	LayoutId   int    `json:"layout_id" xorm:"not null pk autoincr INT(11)"`
	LayoutJson string `json:"layout_json" xorm:"TEXT"`
	ObjId      int    `json:"obj_id" xorm:"INT(11)"`
	TypeId     int    `json:"type_id" xorm:"INT(11)"`
	LayoutType string `json:"layout_type" xorm:"CHAR(10)"`
}
