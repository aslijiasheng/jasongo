package model

type TcUser struct {
	UserId         int    `json:"user_id" xorm:"not null pk autoincr INT(11)"`
	UserName       string `json:"user_name" xorm:"not null VARCHAR(128)"`
	UserSex        int    `json:"user_sex" xorm:"not null INT(11)"`
	UserPassword   string `json:"user_password" xorm:"not null CHAR(100)"`
	UserEmail      string `json:"user_email" xorm:"not null VARCHAR(50)"`
	UserDepartment int    `json:"user_department" xorm:"not null INT(11)"`
	UserGonghao    string `json:"user_gonghao" xorm:"not null VARCHAR(128)"`
	UserDutyName   int    `json:"user_duty_name" xorm:"not null INT(11)"`
	UserStatus     int    `json:"user_status" xorm:"not null INT(11)"`
	UserLoginName  string `json:"user_login_name" xorm:"not null VARCHAR(128)"`
}
