package model

type TcDepartment struct {
	DepartmentId        int    `json:"department_id" xorm:"not null pk autoincr INT(11)"`
	DepartmentName      string `json:"department_name" xorm:"not null VARCHAR(128)"`
	DepartmentUid       int    `json:"department_uid" xorm:"not null INT(11)"`
	DepartmentCode      string `json:"department_code" xorm:"not null VARCHAR(128)"`
	DepartmentTreepath  string `json:"department_treepath" xorm:"not null VARCHAR(225)"`
	DepartmentTreelevel int    `json:"department_treelevel" xorm:"not null INT(11)"`
	DepartmentAuthor    int    `json:"department_author" xorm:"INT(11)"`
	DepartmentIsU8      int    `json:"department_is_u8" xorm:"INT(11)"`
	DepartmentU8Code    string `json:"department_u8_code" xorm:"VARCHAR(128)"`
	DepartmentCrmCode   string `json:"department_crm_code" xorm:"VARCHAR(128)"`
}
