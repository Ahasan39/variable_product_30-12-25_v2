@extends('backEnd.layouts.master')
@section('title','Inbox Messages')

@section('content')

<style>
    .container-d-flex {
        display: flex;
        gap: 20px;
        height: 500px;
    }
    #sender-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
        height: 100%;
        overflow-y: auto;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 250px;
        background: #fff;
    }
    .sender-item {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .sender-item:hover {
        background-color: #e6f0ff;
    }
    .sender-item.selected {
        background-color: #d0e7ff;
        font-weight: 600;
    }
    #right-panel {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        border: 1px solid #ccc;
        border-radius: 6px;
        background: #fafafa;
    }
    #messages-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.4;
    }
    .message {
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        max-width: 80%;
        clear: both;
    }
    .message.user {
        background-color: #fff;
        float: left;
        border: 1px solid #ddd;
    }
    .message.admin {
        background-color: #d4edda;
        float: right;
        border: 1px solid #c3e6cb;
    }
    .timestamp {
        display: block;
        font-size: 11px;
        color: #666;
        margin-top: 5px;
    }
    #reply-box {
        padding: 12px 15px;
        border-top: 1px solid #ccc;
        background: #fff;
        display: none;
    }
    #reply-box textarea {
        width: 100%;
        resize: none;
        padding: 8px;
        font-size: 14px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
</style>

<div class="container-d-flex mt-5">
    <ul id="sender-list">
        <h5 class="p-3 border-bottom">Senders</h5>
        @foreach($senders as $sender)
            <li class="sender-item"
                data-email="{{ $sender->sender_email }}">
                <strong>{{ $sender->sender }}</strong><br>
                <small>{{ $sender->sender_email }}</small>
            </li>
        @endforeach
    </ul>

    <div id="right-panel">
        <div id="messages-container">
            <p>Select a sender from the left to see messages.</p>
        </div>

        <div id="reply-box">
            <textarea id="admin-reply" rows="3" placeholder="Type your reply here..."></textarea>
            <button id="send-reply" class="btn btn-primary mt-2" style="margin-top:8px;">Send Reply</button>
        </div>
    </div>
</div>

<script>
    let selectedSenderEmail = null;
    let selectedMessageId = null;
    let pollingInterval = null;

    document.querySelectorAll('.sender-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.sender-item').forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');

            selectedSenderEmail = this.getAttribute('data-email');
            selectedMessageId = null;
            document.getElementById('reply-box').style.display = 'none';

            fetchMessages(selectedSenderEmail);

            if(pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(() => {
                if(selectedSenderEmail){
                    fetchMessages(selectedSenderEmail);
                }
            }, 10000);
        });
    });

    function fetchMessages(email) {
    const container = document.getElementById('messages-container');
    container.innerHTML = '<p>Loading messages...</p>';

    fetch(`/admin/messages/${encodeURIComponent(email)}`)
        .then(res => {
            if(!res.ok){
                throw new Error('Failed to load messages');
            }
            return res.json();
        })
        .then(messages => {
            container.innerHTML = '';

            if(messages.length === 0){
                container.innerHTML = '<p>No messages from this sender.</p>';
                document.getElementById('reply-box').style.display = 'none';
                selectedMessageId = null;
                return;
            }

            // মেসেজ ডিভগুলো খালি করলাম
            // তারপর সব মেসেজ তৈরি করব
            messages.forEach(msg => {
                const msgDiv = document.createElement('div');
                msgDiv.classList.add('message', 'user');
                msgDiv.textContent = msg.content;

                const timeStamp = document.createElement('span');
                timeStamp.classList.add('timestamp');
                timeStamp.textContent = new Date(msg.created_at).toLocaleString();
                msgDiv.appendChild(timeStamp);

                msgDiv.style.cursor = 'pointer';
                msgDiv.addEventListener('click', () => {
                    selectMessage(msg);
                });

                container.appendChild(msgDiv);

                if(msg.admin_reply){
                    const replyDiv = document.createElement('div');
                    replyDiv.classList.add('message', 'admin');
                    replyDiv.textContent = `Admin Reply: ${msg.admin_reply}`;

                    const replyTime = document.createElement('span');
                    replyTime.classList.add('timestamp');
                    replyTime.textContent = 'Replied at: ' + new Date(msg.updated_at).toLocaleString();
                    replyDiv.appendChild(replyTime);

                    container.appendChild(replyDiv);
                }
            });

            // Scroll to bottom
            container.scrollTop = container.scrollHeight;

            // Auto select last message
            const lastMsg = messages[messages.length - 1];
            selectMessage(lastMsg);
        })
        .catch(err => {
            container.innerHTML = `<p style="color:red;">Error loading messages: ${err.message}</p>`;
            document.getElementById('reply-box').style.display = 'none';
            selectedMessageId = null;
        });
}

function selectMessage(msg) {
    selectedMessageId = msg.id;
    document.getElementById('reply-box').style.display = 'block';
    document.getElementById('admin-reply').value = msg.admin_reply || '';
}


    document.getElementById('send-reply').addEventListener('click', () => {
        const replyText = document.getElementById('admin-reply').value.trim();

        if(!selectedMessageId){
            alert('Please select a message to reply.');
            return;
        }

        if(!replyText){
            alert('Please type your reply.');
            return;
        }

        fetch("{{ route('admin.messages.reply') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                message_id: selectedMessageId,
                admin_reply: replyText
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                fetchMessages(selectedSenderEmail);
                document.getElementById('admin-reply').value = '';
                selectedMessageId = null;
                document.getElementById('reply-box').style.display = 'none';
            } else {
                alert('Failed to send reply. Try again.');
            }
        })
        .catch(() => {
            alert('Error occurred while sending reply.');
        });
    });
</script>

@endsection
