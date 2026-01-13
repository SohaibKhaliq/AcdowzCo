const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/plugins/${directory}`
const dist = `public/vendor/core/plugins/${directory}`

mix
    .js(`${source}/resources/js/shippo.js`, `${dist}/js/shippo.js`)

if (mix.inProduction()) {
    mix.copy(`${dist}/js/shippo.js`, `${source}/public/js`)
}
