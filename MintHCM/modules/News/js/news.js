class News {

    tpl = 'themes/SuiteP/tpls/News.tpl';
    static template;

    constructor(type, record_id, name, content_of_announcement, button_text) {
        this.news_type = type;
        this.record_id = record_id;
        this.name = name;
        this.content_of_announcement = content_of_announcement;
        this.button_text = button_text;
    }

    getNewsBody() {
        return this.loadTpl()({
            type: this.news_type,
            record_id: this.record_id,
            name: this.name,
            content_of_announcement: this.content_of_announcement,
            button_text: this.button_text,
        });
    }

    loadTpl() {
        if (!this.template) {
            $.ajax({
                url: this.tpl,
                async: false,
                success: function (result) {
                    this.template = _.template(result);
                }.bind(this),
            });
        }
        return this.template;
    }
}
