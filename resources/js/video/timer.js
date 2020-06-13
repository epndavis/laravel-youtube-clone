function pad(number, size) {
    let s = String(number);
    while (s.length < (size || 2)) {
        s = '0' + s;
    }

    return s;
}

export function formatTime(durationInSeconds) {
    let durationInMinutes = Math.floor(durationInSeconds) / 60
    let per = durationInMinutes % 1;

    return `${Math.floor(durationInMinutes)}:${pad(Math.round(per * 60), 2)}`
}
