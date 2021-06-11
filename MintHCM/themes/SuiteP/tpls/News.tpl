<div class="mintNews-<%= type %>" news-id="<%= record_id %>">
    <div class="mintNews-box-header"><%= name %>
    </div>
    <div class="mintNews-box-content">
        <div class="mintNews-box-text"><%= content_of_announcement %></div>
        <div class="mintNews-box-button">
            <% if (type === 'reminder') { %>
            <input type="button" value="<%= announcement_label %>" onclick="mintNews.closeBox('<%= record_id %>')" />
            <% } %>
            <input type="button" value="<%= button_text %>" onclick="mintNews.closeBox('<%= record_id %>','<%= type %>')" />
        </div>
    </div>
    <div class="mintNews-box-comments" comment-for-id="<%= record_id %>"><%= comments %></div>
</div>