package model

type DdAuthMenu struct {
	AuthMId   int    `json:"auth_m_id" xorm:"not null pk autoincr INT(10)"`
	RoleId    int    `json:"role_id" xorm:"not null INT(10)"`
	AuthMJson string `json:"auth_m_json" xorm:"not null VARCHAR(256)"`
}
