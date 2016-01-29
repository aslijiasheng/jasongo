package model

type TcRole struct {
	RoleId           int    `json:"role_id" xorm:"not null pk autoincr INT(11)"`
	RoleName         string `json:"role_name" xorm:"not null VARCHAR(128)"`
	RoleDesc         string `json:"role_desc" xorm:"not null VARCHAR(225)"`
	RoleMenuAuth     string `json:"role_menu_auth" xorm:"TEXT"`
	RoleActivityAuth string `json:"role_activity_auth" xorm:"TEXT"`
	RoleDataAuth     string `json:"role_data_auth" xorm:"TEXT"`
}
