package model

type TcUserInfo struct {
	Id      int    `json:"id" xorm:"not null pk autoincr INT(10)"`
	UserId  int    `json:"user_id" xorm:"not null INT(10)"`
	PicPath string `json:"pic_path" xorm:"not null CHAR(50)"`
}
