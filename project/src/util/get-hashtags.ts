export default function (obj: any): string[] {
    let arr: string[] = []
    updateHashtags(obj, arr)
    return arr
}

function updateHashtags(obj: any, arr: string[]) {
    if (!obj) return
    if (obj['content'] && Array.isArray(obj['content'])) {
        for (const c of obj['content']) {
            updateHashtags(c, arr)
        }
    } else if (obj['text']) {
        // check if it is a mark?
        if (
            obj['marks'] &&
            (obj['marks'] as { type: string }[]).some((obj) => obj['type'] === 'hashtag')
        ) {
            //  but should be not matching
            let text: string = obj['text']

            let hashtag = text.slice(1)

            arr.push(hashtag)
        }
    }
}
