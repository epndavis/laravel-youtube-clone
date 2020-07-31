function mousePosition(el, e) {
    return (e.screenX - el.getBoundingClientRect().left) / el.offsetWidth
}

export {
    mousePosition
}