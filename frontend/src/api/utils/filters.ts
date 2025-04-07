export const capitalize = (str: string) => {
    if (!str) return ''
    return str.charAt(0).toUpperCase() + str.slice(1)
}

export const formatDate = (date: Date) => {
    if (!date) return ''
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    }
    return new Date(date).toLocaleString('en-US', options)
}