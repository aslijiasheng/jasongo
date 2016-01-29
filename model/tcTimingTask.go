package model

import (
	"time"
)

type TcTimingTask struct {
	TimingTaskId       int       `json:"timing_task_id" xorm:"not null pk autoincr INT(11)"`
	TimingTaskDoTime   time.Time `json:"timing_task_do_time" xorm:"DATETIME"`
	TimingTaskNextTime time.Time `json:"timing_task_next_time" xorm:"DATETIME"`
	TimingTaskUrl      string    `json:"timing_task_url" xorm:"VARCHAR(225)"`
	TimingTaskNote     string    `json:"timing_task_note" xorm:"VARCHAR(225)"`
	TimingTaskStatus   int       `json:"timing_task_status" xorm:"INT(11)"`
	TimingToken        int       `json:"timing_token" xorm:"INT(11)"`
}
