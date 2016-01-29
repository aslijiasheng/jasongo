package model

type DdAttrtype struct {
	AttrtypeId             int    `json:"attrtype_id" xorm:"not null pk autoincr INT(11)"`
	AttrtypeName           string `json:"attrtype_name" xorm:"not null VARCHAR(128)"`
	AttrtypeViewTemplate   string `json:"attrtype_view_template" xorm:"not null VARCHAR(128)"`
	AttrtypeEditTemplate   string `json:"attrtype_edit_template" xorm:"not null VARCHAR(128)"`
	AttrtypeSelectTemplate string `json:"attrtype_select_template" xorm:"not null VARCHAR(128)"`
	AttrtypeFieldType      string `json:"attrtype_field_type" xorm:"not null VARCHAR(128)"`
}
