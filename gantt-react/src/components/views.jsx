import "gantt-task-react/dist/index.css"
import React from "react"
import { ViewMode } from "gantt-task-react"

export const ViewSwitcher = ({ onViewModeChange, onViewListChange, isChecked }) => {
    return (
        <div classname='ViewContainer'>
            <button className='Button' onClick={() => onViewModeChange(ViewMode.Hour)}>
                Hour
            </button>
            <button className='Button' onClick={() => onViewModeChange(ViewMode.QuarterDay)}>
                Quarter of Day
            </button>
            <button className='Button' onClick={() => onViewModeChange(ViewMode.HalfDay)}>
                Half of Day
            </button>
            <button className='Button' onClick={() => onViewModeChange(ViewMode.Day)}>
                Day
            </button>
            <button className='Button' onClick={() => onViewModeChange(ViewMode.Week)}>
                Week
            </button>
            <button className='Button' onClick={() => onViewModeChange(ViewMode.Month)}>
                Month
            </button>

            <div className='Switch'>
                <label className='Switch_Toggle'>
                    <input
                        type='checkbox'
                        id='checktask'
                        defaultChecked={isChecked}
                        onClick={() => onViewListChange(!isChecked)}
                    />
                    <span className='Slider' />
                </label>
                Show Task List
            </div>
        </div>
    )
}