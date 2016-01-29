package model

type DdMenu struct {
	MenuId        int    `json:"menu_id" xorm:"not null pk autoincr INT(11)"`
	MenuName      string `json:"menu_name" xorm:"CHAR(255)"`
	MenuUid       int    `json:"menu_uid" xorm:"INT(11)"`
	MenuUrl       string `json:"menu_url" xorm:"CHAR(255)"`
	MenuLabel     string `json:"menu_label" xorm:"not null VARCHAR(30)"`
	MenuIsJs      int    `json:"menu_is_js" xorm:"default 0 TINYINT(4)"`
	MenuJsContent string `json:"menu_js_content" xorm:"TEXT"`
	MenuIcon      string `json:"menu_icon" xorm:"CHAR(255)"`
	MenuPath      string `json:"menu_path" xorm:"VARCHAR(255)"`
	MenuOrder     int    `json:"menu_order" xorm:"default 0 INT(3)"`
}
