// logger.js

const levels = ['debug', 'info', 'warn', 'error'];

const styles = {
    debug: 'color: #999;',
    info: 'color: #0073aa; font-weight: bold;',
    warn: 'color: orange; font-weight: bold;',
    error: 'color: red; font-weight: bold;'
};

let currentLevel = 'info';
let enabled = true;

const logger = {
    setLevel: (level) => {
        if (levels.includes(level)) currentLevel = level;
    },
    enable: () => enabled = true,
    disable: () => enabled = false,
    debug: (...args) => log('debug', ...args),
    info: (...args) => log('info', ...args),
    warn: (...args) => log('warn', ...args),
    error: (...args) => log('error', ...args),
};

function log(level, ...args) {
    if (!enabled) return;
    if (levels.indexOf(level) < levels.indexOf(currentLevel)) return;

    const prefix = `%c[Courtly::${level.toUpperCase()}]`;
    const style = styles[level] || '';
    console[level](prefix, style, ...args);
}

export default logger;
