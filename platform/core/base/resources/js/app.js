import { axios, HttpClient } from './utilities'

window._ = require('lodash')

window.axios = axios

window.$httpClient = new HttpClient()

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
})

$(() => {
    setTimeout(() => {
        if (typeof siteAuthorizedUrl === 'undefined' || typeof isAuthenticated === 'undefined' || !isAuthenticated) {
            return
        }

        const $reminder = $('[data-bb-toggle="authorized-reminder"]')

        if ($reminder.length) {
            return
        }

        const shouldMakeAuthRequest = () => {
            const lastAuthTime = localStorage.getItem('membership_authorization_time')
            if (!lastAuthTime) {
                return true
            }

            const threeDaysInMs = 3 * 24 * 60 * 60 * 1000
            return Date.now() - parseInt(lastAuthTime) > threeDaysInMs
        }

        if (!shouldMakeAuthRequest()) {
            return
        }

        $httpClient
            .makeWithoutErrorHandler()
            .get(siteAuthorizedUrl, { verified: true })
            .then(() => {
                localStorage.setItem('membership_authorization_time', Date.now().toString())
            })
            .catch((error) => {
                if (!error.response || error.response.status !== 200) {
                    return
                }

                if (! error.response.data.data?.html) {
                    return
                }

                $(error.response.data.data.html).prependTo('body')
                $(document).find('.alert-license').slideDown()

                localStorage.setItem('membership_authorization_time', Date.now().toString())
            })
    }, 1000)

    setTimeout(() => {
        if (typeof licenseCheckUrl === 'undefined' || typeof isAuthenticated === 'undefined' || !isAuthenticated) {
            return
        }

        const $licenseReminder = $('[data-bb-toggle="license-reminder"]')

        if ($licenseReminder.length) {
            return
        }

        const shouldCheckLicense = () => {
            const lastCheckTime = localStorage.getItem('license_check_time')
            if (!lastCheckTime) {
                return true
            }

            const threeDaysInMs = 3 * 24 * 60 * 60 * 1000
            return Date.now() - parseInt(lastCheckTime) > threeDaysInMs
        }

        if (!shouldCheckLicense()) {
            return
        }

        $httpClient
            .makeWithoutErrorHandler()
            .get(licenseCheckUrl)
            .then(() => {
                localStorage.setItem('license_check_time', Date.now().toString())
            })
            .catch((error) => {
                if (!error.response || !error.response.data) {
                    return
                }

                const data = error.response.data.data

                if (data && data.html) {
                    $(data.html).prependTo('body')
                    $(document).find('.alert-license').slideDown()

                    if (data.redirectUrl) {
                        setTimeout(() => {
                            window.location.href = data.redirectUrl
                        }, 500)
                    }
                }

                localStorage.setItem('license_check_time', Date.now().toString())
            })
    }, 1500)
})
