package model

type TcRoleUser struct {
	UserId int `json:"user_id" xorm:"not null pk default 0 INT(11)"`
	RoleId int `json:"role_id" xorm:"not null pk default 0 INT(11)"`
}
