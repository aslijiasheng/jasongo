package model

import (
	"time"
)

type TcExpressMail struct {
	ExpressId            int       `json:"express_id" xorm:"not null pk autoincr INT(11)"`
	ExpressFromUserId    string    `json:"express_from_user_id" xorm:"not null VARCHAR(20)"`
	ExpressToUserId      string    `json:"express_to_user_id" xorm:"not null VARCHAR(20)"`
	ExpressCreateUserId  string    `json:"express_create_user_id" xorm:"not null VARCHAR(20)"`
	ExpressCreateDate    time.Time `json:"express_create_date" xorm:"not null default 'CURRENT_TIMESTAMP' TIMESTAMP"`
	ExpressToExpressDate time.Time `json:"express_to_express_date" xorm:"not null default '0000-00-00 00:00:00' TIMESTAMP"`
	ExpressTakeStatus    int       `json:"express_take_status" xorm:"not null INT(1)"`
}
