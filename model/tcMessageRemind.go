package model

type TcMessageRemind struct {
	MessageRemindId     int `json:"message_remind_id" xorm:"not null pk autoincr INT(11)"`
	MessageRemindRoleId int `json:"message_remind_role_id" xorm:"INT(11)"`
	MessageRemindStatus int `json:"message_remind_status" xorm:"INT(11)"`
}
