@if (!empty($messages))
    <div class="checkout-warnings mb-4">
        @foreach ($messages as $message)
            <div class="checkout-warning-alert" style="
                background-color: #fff3cd;
                border: 1px solid #ffeaa7;
                border-radius: 8px;
                padding: 16px 20px;
                margin-bottom: 12px;
                font-size: 14px;
                line-height: 28px;
                color: #856404;
                display: flex;
                align-items: flex-start;
                gap: 12px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border-left: 4px solid #ffc107;
            ">
                <div style="
                    flex-shrink: 0;
                    width: 24px;
                    height: 24px;
                    background-color: #ffc107;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-top: 2px;
                ">
                    <svg style="width: 14px; height: 14px; fill: #fff;" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                </div>
                <div style="flex: 1; font-weight: 500;">
                    {{ $message }}
                </div>
            </div>
        @endforeach
    </div>
@endif
