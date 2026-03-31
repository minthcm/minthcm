class KReportsScrollManager {
    static SCROLL_SELECTOR = '#KReportViewer-PresentationContainer-1036 > div.top-scroll-wrapper'
    static REPORT_SELECTOR = '#KReportViewer-PresentationContainer-1036'
    static TABLE_SELECTOR = '#KReportViewer-PresentationContainer-1036-innerCt > div'
    static HEADER_SELECTOR = '#KReportViewer-PresentationContainer-1036-innerCt > div > div.x-grid-header-ct'

    constructor() {
        /** @type {HTMLDivElement} */
        this.topScrollWrapper = null

        /** @type {HTMLDivElement} */
        this.dummyWidthElement = null
    }

    init() {
        if (document.querySelector(KReportsScrollManager.SCROLL_SELECTOR)) {
            return // scroll is already in the report
        }
        if (!this.isReportGenerated()) {
            return setTimeout(() => this.init(), 250) // wait for report to render
        }

        this.topScrollWrapper = document.createElement('div')
        this.topScrollWrapper.classList.add('top-scroll-wrapper')
        document.querySelector(KReportsScrollManager.REPORT_SELECTOR)?.prepend(this.topScrollWrapper)

        this.dummyWidthElement = document.createElement('div')
        this.topScrollWrapper.prepend(this.dummyWidthElement)

        this.startScrollWidthSync()
        this.setEvents()
    }

    isReportGenerated() {
        return (
            document.querySelector(KReportsScrollManager.TABLE_SELECTOR) &&
            document.querySelector(KReportsScrollManager.HEADER_SELECTOR) &&
            document.querySelector(KReportsScrollManager.REPORT_SELECTOR)
        )
    }

    setEvents() {
        const reportTable = document.querySelector(KReportsScrollManager.TABLE_SELECTOR)
        if (!reportTable) {
            return
        }
        this.topScrollWrapper.addEventListener('scroll', () => {
            if (this.topScrollWrapper && reportTable) {
                reportTable.scrollLeft = this.topScrollWrapper.scrollLeft
            }
        })
        reportTable.addEventListener('scroll', () => {
            if (this.topScrollWrapper && reportTable) {
                this.topScrollWrapper.scrollLeft = reportTable.scrollLeft
            }
        })
    }

    startScrollWidthSync() {
        setInterval(() => {
            const tableHeader = document.querySelector(KReportsScrollManager.HEADER_SELECTOR)
            if (this.dummyWidthElement && tableHeader) {
                this.dummyWidthElement.style.width = Math.floor(tableHeader.getBoundingClientRect().width - 1) + 'px'
            }
            this.calculateOverflows()
        }, 250)
    }

    calculateOverflows() {
        const reportTable = document.querySelector(KReportsScrollManager.TABLE_SELECTOR)
        if (!reportTable || !this.topScrollWrapper) {
            return
        }
        if (this.isTopScrollVisible()) {
            reportTable.style.removeProperty('overflow-x')
        } else {
            reportTable.style.overflowX = 'hidden'
        }
    }

    isTopScrollVisible() {
        if (this.topScrollWrapper) {
            return this.topScrollWrapper.clientWidth < this.topScrollWrapper.scrollWidth
        }
        return false
    }
}

new KReportsScrollManager().init()
