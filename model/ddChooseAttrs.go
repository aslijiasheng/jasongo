package model

type DdChooseAttrs struct {
	DcaId      int    `json:"dca_id" xorm:"not null pk autoincr INT(11)"`
	ChooseAttr string `json:"choose_attr" xorm:"CHAR(100)"`
	ObjId      int    `json:"obj_id" xorm:"INT(11)"`
	TypeId     int    `json:"type_id" xorm:"INT(11)"`
	PageType   string `json:"page_type" xorm:"CHAR(10)"`
}
