Number.prototype.pad = function(size) {
    let s = String(this);
    while (s.length < (size || 2)) {
        s = '0' + s;
    }

    return s;
}

export function formatTime(durationInSeconds) {
    let durationInMinutes = durationInSeconds / 60
    let per = durationInMinutes % 1;

    return `${Math.floor(durationInMinutes)}:${Math.floor(per * 60).pad(2)}`
}
