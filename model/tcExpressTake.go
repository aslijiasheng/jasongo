package model

import (
	"time"
)

type TcExpressTake struct {
	ExpressTakeId        int       `json:"express_take_id" xorm:"not null pk autoincr INT(11)"`
	ExpressTakeExpressId int       `json:"express_take_express_id" xorm:"not null INT(11)"`
	ExpressTakeUserId    string    `json:"express_take_user_id" xorm:"not null VARCHAR(20)"`
	ExpressTakeDate      time.Time `json:"express_take_date" xorm:"not null default 'CURRENT_TIMESTAMP' TIMESTAMP"`
}
