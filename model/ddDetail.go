package model

type DdDetail struct {
	Id           int `json:"id" xorm:"not null pk autoincr INT(11)"`
	ObjId        int `json:"obj_id" xorm:"not null INT(11)"`
	DetailObjId  int `json:"detail_obj_id" xorm:"not null INT(11)"`
	DetailAttrId int `json:"detail_attr_id" xorm:"not null INT(9)"`
	TypeId       int `json:"type_id" xorm:"not null default 0 INT(9)"`
}
