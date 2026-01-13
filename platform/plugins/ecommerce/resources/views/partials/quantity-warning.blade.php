<div class="quantity-warning-alert" style="
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 6px;
    padding: 12px 16px;
    margin-top: 8px;
    font-size: 14px;
    line-height: 1.4;
    color: #856404;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
">
    <div style="
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        background-color: #ffc107;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
    ">
        <svg style="width: 12px; height: 12px; fill: #fff;" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
    </div>
    <div style="flex: 1;">
        {{ $message }}
    </div>
</div>
