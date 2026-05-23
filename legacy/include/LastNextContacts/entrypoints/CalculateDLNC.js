'use strict';
window.addEventListener('DOMContentLoaded', () => {
    const alert = document.querySelector('#alert'),
        calculate = document.querySelector('#calculate'),
        form = document.querySelector('.form'),
        progressbar_status_div = document.querySelector('.progressbar_status'),
        progressbar_status_1 = document.querySelector('#progressbar_status--1'),
        progressbar_status_2 = document.querySelector('#progressbar_status--2'),
        progressbar = document.querySelector('#progressbar'),
        progressbar_background = document.querySelector('.progressbar'),
        part_size = 10;

    let parts = 0, progressbar_value = 0;

    let time = 0, time_list = [], avg_time = 0;
    const timer = setInterval(() => {
        time++;
    }, 1000);
    const update_time = () => {
        time_list.push(time);
        time = 0;
        const time_sum = time_list.reduce((total, num) => (total + num));
        avg_time = Math.floor(time_sum / time_list.length);
    };
    const reset_time = () => {
        avg_time = 0
        time = 0;
        time_list = [];
        progressbar_status_1.textContent = '0/0';
        progressbar_status_2.textContent = '?s';
    };

    const { protocol, host, pathname } = window.location;
    const method = 'POST', url = `${protocol}//${host}${pathname}?entryPoint=CalculateDLNCcalc`;

    const calc = (data, part = 0) => {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.onload = e => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const result = JSON.parse(xhr.responseText.toString());
                if (result.status) {
                    if (part < parts) {
                        calc(data, part + 1);
                    } else {
                        alert.style.display = 'flex';
                        calculate.disabled = false;
                        form.style.animation = '';
                        form.style.gridTemplateRows = 'repeat(3, 40px)';
                        reset_time();
                    }
                    update_time();
                    progressbar.style.width = `${part / parts * 100}%`;
                    const records = part * part_size;
                    progressbar_status_1.textContent = `${records > progressbar_value ? progressbar_value : records}/${progressbar_value}`;
                    progressbar_status_2.textContent = `${avg_time * (parts - part)}s`;
                } else {
                    console.error(xhr.statusText);
                }
            } else {
                console.error(xhr.statusText);
            }
        }
        xhr.onerror = e => {
            console.error(xhr.statusText);
        }
        const form_data = new FormData();
        form_data.append("module_name", data.module_name);
        form_data.append("null_records", data.null_records);
        form_data.append("return_only_count", data.return_only_count);
        form_data.append("part", part);
        xhr.send(form_data);
    }
    const progressbar_value_update = (post_data) => {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.onload = e => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(xhr.responseText.toString());
                if (data.status) {
                    progressbar_value = data.value;
                    parts = Math.ceil(data.value / part_size);
                    post_data.return_only_count = false;
                    calc(post_data);
                } else {
                    console.error(xhr.statusText);
                }
            } else {
                console.error(xhr.statusText);
            }
        }
        xhr.onerror = e => {
            console.error(xhr.statusText);
        }
        const form_data = new FormData();
        form_data.append("module_name", post_data.module_name);
        form_data.append("null_records", post_data.null_records);
        form_data.append("return_only_count", post_data.return_only_count);
        xhr.send(form_data);
    }
    calculate.addEventListener('click', () => {
        calculate.disabled = true;
        progressbar_background.style.display = 'block';
        progressbar_status_div.style.display = 'flex';
        form.style.animation = 'progress_animation 1s infinite';
        form.style.gridTemplateRows = 'repeat(3, 40px) 5px 12px';
        reset_time();
        const data = {
            module_name: document.querySelector('#module_name').value,
            null_records: document.querySelector('#only_null_records').checked,
            return_only_count: true,
        };
        progressbar_value_update(data);
    });
    alert.addEventListener('click', () => {
        alert.style.display = 'none';
        progressbar.style.width = '0%';
        progressbar_background.style.display = 'none';
        progressbar_status_div.style.display = 'none';
        form.style.gridTemplateRows = 'repeat(3, 40px)';
    });
});