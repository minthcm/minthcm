if (typeof Reactions !== 'function') {
    class Reactions {
        constructor() {
            this.wrapper = $('.reactionsWrapper');
            this.reactions = {};
        }
        initOnDetailView() {
            const module_name = $('form#formDetailView input[type=hidden][name=module]').val();
            const record_id = $('form#formDetailView input[type=hidden][name=record]').val();
            if (record_id && module_name && this.wrapper.length) {
                this.displayReactions(module_name, record_id);
            }
        }
        displayReactions(module_name, record_id) {
            viewTools.api.callCustomApi({
                module: 'Reactions',
                action: 'getReactions',
                dataPOST: {
                    module_name: module_name,
                    record_id: record_id
                },
                callback: function (reactions) {
                    if (reactions) {
                        this.reactions = reactions;
                        this.displayReactionsButtons();
                    }
                }.bind(this)
            });
        }
        displayReactionsButtons() {
            let buttons_html = "";
            for (const reaction in this.reactions) {
                buttons_html += this.getReactionButton(reaction);
            }
            this.wrapper.html(buttons_html);
        }
        getReactionButton(reaction) {
            return this.getReactionButtonTemplate()({
                icon: this.getReactionButtonIcon(reaction),
                active: this.getReactionButtonActive(reaction),
                count: this.getReactionButtonCount(reaction),
                title: this.getReactionButtonTitle(reaction),
                onclick: this.getReactionButtonOnclick(reaction),
            });
        }
        getReactionButtonTemplate() {
            return _.template('<button <%= active %> type="button" title="<%= title %>" onclick="<%= onclick %>"><span class="fas fa-<%= icon %>"></span><%= count %></button>');
        }
        getReactionButtonTitle(reaction) {
            let users = Object.values(this.reactions[reaction].users);
            if (users.length > 10) {
                users = users.splice(0, 10);
            }
            return users.join("\n");
        }
        getReactionButtonActive(reaction) {
            return this.reactions[reaction].active ? 'active' : '';
        }
        getReactionButtonCount(reaction) {
            return Object.values(this.reactions[reaction].users).length ? ' (' + Object.values(this.reactions[reaction].users).length + ')' : '';
        }
        getReactionButtonOnclick(reaction) {
            const module_name = $('form#formDetailView input[type=hidden][name=module]').val();
            const record_id = $('form#formDetailView input[type=hidden][name=record]').val();
            const function_name = this.reactions[reaction].active ? 'removeReaction' : 'addReaction';
            return "Reactions." + function_name + "('" + reaction + "','" + module_name + "','" + record_id + "')";
        }
        getReactionButtonIcon(reaction) {
            return viewTools.language.get('app_list_strings', 'reaction_icons_list')[reaction];
        }
        addReaction(reaction_type, module_name, record_id) {
            viewTools.api.callCustomApi({
                module: 'Reactions',
                action: 'addReaction',
                dataPOST: {
                    reaction_type: reaction_type,
                    module_name: module_name,
                    record_id: record_id
                },
                callback: function (reaction_type, button, result) {
                    if (result) {
                        this.reactions[reaction_type].active = true;
                        this.reactions[reaction_type].users[result.id] = result.name;
                        $(button).replaceWith(this.getReactionButton(reaction_type));
                    }
                }.bind(this, reaction_type, event.currentTarget)
            });
        }
        removeReaction(reaction_type, module_name, record_id) {
            viewTools.api.callCustomApi({
                module: 'Reactions',
                action: 'removeReaction',
                dataPOST: {
                    reaction_type: reaction_type,
                    module_name: module_name,
                    record_id: record_id
                },
                callback: function (reaction_type, button, result) {
                    if (result) {
                        this.reactions[reaction_type].active = false;
                        delete this.reactions[reaction_type].users[result];
                        $(button).replaceWith(this.getReactionButton(reaction_type));
                    }
                }.bind(this, reaction_type, event.currentTarget)
            });
        }
    }
    window.Reactions = Reactions;
}

$(document).ready(function () {
    window.Reactions = new Reactions();
    Reactions.initOnDetailView();
});
