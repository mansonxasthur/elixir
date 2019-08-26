import ar from '../locale/ar';
import en from '../locale/en';

const locales = {
    ar,
    en
};

export default function (locale, key, property, nested) {
    if (nested !== '') {
        return locales[locale][key][property][nested];
    }
    return locales[locale][key][property];
}