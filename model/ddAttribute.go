package model

type DdAttribute struct {
	AttrId        int    `json:"attr_id" xorm:"not null pk autoincr INT(11)"`
	AttrObjId     int    `json:"attr_obj_id" xorm:"not null INT(11)"`
	AttrName      string `json:"attr_name" xorm:"not null VARCHAR(255)"`
	AttrLabel     string `json:"attr_label" xorm:"not null VARCHAR(255)"`
	AttrType      int    `json:"attr_type" xorm:"not null INT(11)"`
	AttrFieldName string `json:"attr_field_name" xorm:"not null VARCHAR(255)"`
	AttrQuoteId   int    `json:"attr_quote_id" xorm:"not null INT(11)"`
}
