<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* Floating Action Button Styles */
    .fab-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
    }

    .fab-main {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #FF6B6B;
        border: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .fab-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #FF6B6B;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 50%;
    }

    .fab-main:hover::before {
        opacity: 1;
    }

    .fab-main:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .fab-main.active {
        transform: rotate(45deg);
        background: linear-gradient(45deg, #ff4757, #ff3838);
    }

    .fab-main i {
        transition: transform 0.3s ease;
        position: relative;
        z-index: 2;
    }

    /* Action Buttons */
    .fab-actions {
        position: absolute;
        bottom: 70px;
        right: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .fab-action {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0;
        transform: translateY(20px) scale(0);
        position: relative;
        text-decoration: none;
    }

    .fab-action::before {
        content: attr(data-tooltip);
        position: absolute;
        right: 60px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .fab-action::after {
        content: '';
        position: absolute;
        right: 55px;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-left-color: rgba(0, 0, 0, 0.8);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .fab-action:hover::before,
    .fab-action:hover::after {
        opacity: 1;
    }

    .fab-action:hover {
        transform: translateX(-10px) scale(1.1);
        color: white;
        text-decoration: none;
    }

    /* Action Button Colors */
    .fab-action.back {
        background: linear-gradient(45deg, #6c757d, #5a6268);
    }

    .fab-action.edit {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }

    .fab-action.resolve {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }

    .fab-action.comment {
        background: linear-gradient(45deg, #ffc107, #e0a800);
    }

    .fab-action.create {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }

    .fab-action.filter {
        background: linear-gradient(45deg, #17a2b8, #138496);
    }

    .fab-action.sync {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }

    /* Animation States */
    .fab-container.active .fab-action {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .fab-container.active .fab-action:nth-child(1) {
        transition-delay: 0.1s;
    }

    .fab-container.active .fab-action:nth-child(2) {
        transition-delay: 0.15s;
    }

    .fab-container.active .fab-action:nth-child(3) {
        transition-delay: 0.2s;
    }

    .fab-container.active .fab-action:nth-child(4) {
        transition-delay: 0.25s;
    }

    /* Backdrop */
    .fab-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1040;
    }

    .fab-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    /* Pulse Animation */
    .fab-main.pulse::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border: 2px solid rgba(255, 107, 107, 0.6);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.8);
            opacity: 1;
        }

        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .fab-container {
            bottom: 20px;
            right: 20px;
        }

        .fab-main {
            width: 56px;
            height: 56px;
            font-size: 20px;
        }

        .fab-action {
            width: 44px;
            height: 44px;
            font-size: 16px;
        }

        .fab-action::before {
            right: 50px;
            font-size: 12px;
            padding: 6px 10px;
        }
    }

    #dataTable tbody tr {
        cursor: pointer;
    }

    #dataTable tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #dataTable tbody tr button {
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 0.25rem;
        padding: 30px;
        text-align: center;
        color: #555;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .drop-zone--over {
        border-color: #007bff;
        /* Bootstrap primary color */
        background-color: #f0f8ff;
        /* A light blue */
    }

    /* Hide the default file input, the drop-zone will trigger it */
    .drop-zone .custom-file-input {
        display: none;
    }
</style>