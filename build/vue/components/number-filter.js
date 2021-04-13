import formatter from 'number-formatter'

export default function (v, format) {
    format = format || '# ###,00';
    return formatter(format, v);
}