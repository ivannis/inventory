import React from 'react'
import { useTabState } from '@bumaga/tabs'

const cn = (...args) => args.filter(Boolean).join(' ')

const Tab = ({ children }) => {
    const { isActive, onClick } = useTabState()

    return (
        <button className={cn('tab', isActive && 'active')} onClick={onClick}>
            {children}
        </button>
    )
}

export default Tab;
