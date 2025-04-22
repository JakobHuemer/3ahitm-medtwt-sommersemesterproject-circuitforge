// AI-Notice: heavily inspired by claude.ai

import { defineStore } from 'pinia'
import { ref } from 'vue'
import { EventEmitter, type Listener } from 'events'

let idCounter = 0

export enum NoticeType {
    SUCCESS = 'success',
    INFO = 'info',
    WARN = 'warn',
    ERROR = 'error',
}

export interface Notice {
    id: number
    name: string
    type: NoticeType
    message: string
    data?: any
    throwable?: Error
    timestamp: number
}

export interface ErrorNotice extends Notice {
    type: NoticeType.ERROR
    throwable?: Error
}

export const useNotice = defineStore('error', () => {
    const errorsMap = ref<Map<string, Notice[]>>(new Map())
    const emitter = new EventEmitter()

    /**
     * Takes a Notice, pushes it to the queue with the "name" and emits an event
     * @param name the name of the error queue
     * @param type success, info, warn, error
     * @param message an optional message to use for the notice
     * @param throwable an optional TypeScript Error
     * @param data optional data which might be provided for extra context
     * @return the notice created by this function
     */
    function pushNotice(
        name: string,
        type: NoticeType,
        message: string,
        data?: any,
        throwable?: Error,
    ): Notice {
        const notice: Notice = {
            id: ++idCounter,
            type,
            name,
            message,
            data,
            throwable,
            timestamp: Date.now(),
        }

        const noticeIndexName = `${type}:${name}`

        if (!errorsMap.value.has(noticeIndexName)) {
            errorsMap.value.set(noticeIndexName, [])
        }

        const queue = errorsMap.value.get(noticeIndexName)!

        queue.push(notice)

        emitter.emit('notice', notice)
        emitter.emit(notice.type, notice)

        return notice
    }

    function success(name: string, message: string, data?: any): Notice {
        return pushNotice(name, NoticeType.SUCCESS, message, data)
    }

    function info(name: string, message: string, data?: any): Notice {
        return pushNotice(name, NoticeType.INFO, message, data)
    }

    function warn(name: string, message: string, data?: any): Notice {
        return pushNotice(name, NoticeType.WARN, message, data)
    }

    function error(name: string, message: string, data?: any, throwable?: Error): Notice {
        return pushNotice(name, NoticeType.ERROR, message, data, throwable)
    }

    function pop(type: NoticeType, name: string): Notice | undefined {
        const errors = errorsMap.value.get(`${type}:${name}`)

        if (!errors || errors.length <= 0) {
            return undefined
        }

        return errors.shift()
    }

    function peek(type: NoticeType, name: string): Notice | undefined {
        const errors = errorsMap.value.get(`${type}:${name}`)

        if (!errors || errors.length <= 0) {
            return undefined
        }

        return errors[0]
    }

    function get(type: NoticeType, name: string): Notice[] | undefined {
        return errorsMap.value.get(`${type}:${name}`)
    }

    function getAll(name: string): Notice[] {
        return [
            ...(get(NoticeType.SUCCESS, name) ?? []),
            ...(get(NoticeType.INFO, name) ?? []),
            ...(get(NoticeType.WARN, name) ?? []),
            ...(get(NoticeType.ERROR, name) ?? []),
        ]
    }

    function on(name: string, callback: Listener) {
        emitter.on(name, callback)
    }

    function off(name: string, callback: Listener) {
        emitter.off(name, callback)
    }

    return {
        pushNotice,
        success,
        info,
        warn,
        error,
        pop,
        peek,
        get,
        getAll,
        on,
        off,
    }
})
