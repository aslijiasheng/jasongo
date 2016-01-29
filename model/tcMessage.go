package model

type TcMessage struct {
	MessageId         int    `json:"message_id" xorm:"not null pk autoincr INT(11)"`
	MessageOwner      int    `json:"message_owner" xorm:"INT(11)"`
	MessageModuleId   int    `json:"message_module_id" xorm:"INT(11)"`
	MessageType       int    `json:"message_type" xorm:"INT(11)"`
	MessageUrl        string `json:"message_url" xorm:"VARCHAR(128)"`
	MessageStatus     int    `json:"message_status" xorm:"default 1001 INT(11)"`
	MessageModule     string `json:"message_module" xorm:"VARCHAR(128)"`
	MessageDepartment int    `json:"message_department" xorm:"default 1 INT(11)"`
}
