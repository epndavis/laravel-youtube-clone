import { formatTime } from '../../../resources/js/video/timer'

test('shows seconds less than 10 add leading zeros', () => {
    expect(formatTime(9)).toBe('0:09');
})

test('shows seconds greater than a minute can show the minute with remaining seconds' , () => {
    expect(formatTime(65)).toBe('1:05');
})

test('round down seconds with decimal', () => {
    expect(formatTime(29.795)).toBe('0:29')
})
