<?php

namespace App\Helpers;


class DynamicSvg
{

    public static function getIcon($kind, $type = "white" ): string
    {

        $colorty = $type == "white"?"#ffffff": "#2D4291";

        $icons = (object) [
            'home' => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 14L14 2L26 14" stroke='.$colorty.' stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M4.66675 11.3335V22.0002C4.66675 22.3538 4.80722 22.6929 5.05727 22.943C5.30732 23.193 5.64646 23.3335 6.00008 23.3335H10.0001C10.3537 23.3335 10.6928 23.193 10.9429 22.943C11.1929 22.6929 11.3334 22.3538 11.3334 22.0002V16.6668C11.3334 16.3132 11.4739 15.9741 11.7239 15.724C11.974 15.474 12.3131 15.3335 12.6667 15.3335H15.3334C15.687 15.3335 16.0262 15.474 16.2762 15.724C16.5263 15.9741 16.6667 16.3132 16.6667 16.6668V22.0002C16.6667 22.3538 16.8072 22.6929 17.0573 22.943C17.3073 23.193 17.6465 23.3335 18.0001 23.3335H22.0001C22.3537 23.3335 22.6928 23.193 22.9429 22.943C23.1929 22.6929 23.3334 22.3538 23.3334 22.0002V11.3335" stroke='.$colorty.' stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>',
            'payment' => '<svg width="28" height="29" viewBox="0 0 28 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18.5 17.3712C18.2613 17.3712 18.0324 17.466 17.8636 17.6348C17.6948 17.8036 17.6 18.0325 17.6 18.2712C17.6 18.5099 17.6948 18.7388 17.8636 18.9076C18.0324 19.0764 18.2613 19.1712 18.5 19.1712H21.5C21.7387 19.1712 21.9676 19.0764 22.1364 18.9076C22.3052 18.7388 22.4 18.5099 22.4 18.2712C22.4 18.0325 22.3052 17.8036 22.1364 17.6348C21.9676 17.466 21.7387 17.3712 21.5 17.3712H18.5ZM4.75 5.97119C4.02065 5.97119 3.32118 6.26092 2.80546 6.77665C2.28973 7.29237 2 7.99185 2 8.72119V20.2212C2 20.9505 2.28973 21.65 2.80546 22.1657C3.32118 22.6815 4.02065 22.9712 4.75 22.9712H23.25C23.9793 22.9712 24.6788 22.6815 25.1945 22.1657C25.7103 21.65 26 20.9505 26 20.2212V8.72119C26 7.99185 25.7103 7.29237 25.1945 6.77665C24.6788 6.26092 23.9793 5.97119 23.25 5.97119H4.75ZM3.5 20.2212V12.4712H24.5V20.2212C24.5 20.9112 23.94 21.4712 23.25 21.4712H4.75C4.06 21.4712 3.5 20.9112 3.5 20.2212ZM3.5 10.9712V8.72119C3.5 8.03119 4.06 7.47119 4.75 7.47119H23.25C23.94 7.47119 24.5 8.03119 24.5 8.72119V10.9712H3.5Z" fill='.$colorty.' />
            </svg>',
            'history' => '<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="13.2" cy="13.0139" r="11.8" stroke='.$colorty.' stroke-width="2"/>
            </svg>',
            'settings' =>'<svg width="28" height="28" viewBox="0 0 28 28" fill='.$colorty.' xmlns="http://www.w3.org/2000/svg">
            <path d="M16.4256 1.75C16.6108 1.75001 16.7913 1.80881 16.941 1.91793C17.0907 2.02706 17.2019 2.18089 17.2586 2.35725L18.2211 5.348C18.6253 5.54575 19.0121 5.768 19.3813 6.01825L22.4543 5.35675C22.6355 5.31807 22.8243 5.33789 22.9935 5.41337C23.1627 5.48885 23.3036 5.61608 23.3958 5.77675L25.8213 9.975C25.9139 10.1356 25.9532 10.3214 25.9333 10.5057C25.9135 10.69 25.8357 10.8633 25.7111 11.0005L23.6023 13.328C23.633 13.7743 23.633 14.2222 23.6023 14.6685L25.7111 16.9995C25.8357 17.1367 25.9135 17.31 25.9333 17.4943C25.9532 17.6786 25.9139 17.8644 25.8213 18.025L23.3958 22.225C23.3033 22.3853 23.1623 22.5122 22.9931 22.5874C22.824 22.6625 22.6353 22.6821 22.4543 22.6432L19.3813 21.9818C19.0138 22.2302 18.6253 22.4543 18.2228 22.652L17.2586 25.6427C17.2019 25.8191 17.0907 25.9729 16.941 26.0821C16.7913 26.1912 16.6108 26.25 16.4256 26.25H11.5746C11.3893 26.25 11.2088 26.1912 11.0591 26.0821C10.9095 25.9729 10.7983 25.8191 10.7416 25.6427L9.78082 22.6537C9.37765 22.4566 8.98878 22.2315 8.61707 21.98L5.54582 22.6432C5.36463 22.6819 5.17584 22.6621 5.00664 22.5866C4.83745 22.5112 4.69657 22.3839 4.60432 22.2233L2.17882 18.025C2.0862 17.8644 2.04697 17.6786 2.06678 17.4943C2.0866 17.31 2.16443 17.1367 2.28907 16.9995L4.39782 14.6685C4.36727 14.2234 4.36727 13.7766 4.39782 13.3315L2.28907 11.0005C2.16443 10.8633 2.0866 10.69 2.06678 10.5057C2.04697 10.3214 2.0862 10.1356 2.17882 9.975L4.60432 5.775C4.69683 5.61465 4.83781 5.48778 5.00699 5.41263C5.17616 5.33747 5.36482 5.31791 5.54582 5.35675L8.61707 6.02C8.98807 5.76975 9.37657 5.544 9.78082 5.34625L10.7433 2.35725C10.7998 2.18145 10.9105 2.02804 11.0595 1.91896C11.2085 1.80989 11.3882 1.75074 11.5728 1.75H16.4238H16.4256ZM15.7851 3.5H12.2151L11.2211 6.59225L10.5508 6.9195C10.2213 7.08074 9.90325 7.26434 9.59882 7.469L8.97932 7.889L5.80132 7.203L4.01632 10.297L6.19507 12.7085L6.14257 13.4505C6.11742 13.8164 6.11742 14.1836 6.14257 14.5495L6.19507 15.2915L4.01282 17.703L5.79957 20.797L8.97757 20.1128L9.59707 20.531C9.9015 20.7357 10.2196 20.9193 10.5491 21.0805L11.2193 21.4077L12.2151 24.5H15.7886L16.7861 21.406L17.4546 21.0805C17.7837 20.9196 18.1012 20.736 18.4048 20.531L19.0226 20.1128L22.2023 20.797L23.9873 17.703L21.8068 15.2915L21.8593 14.5495C21.8845 14.183 21.8845 13.8152 21.8593 13.4487L21.8068 12.7067L23.9891 10.297L22.2023 7.203L19.0226 7.8855L18.4048 7.469C18.1013 7.26393 17.7837 7.08031 17.4546 6.9195L16.7861 6.594L15.7868 3.5H15.7851ZM14.0001 8.75C15.3925 8.75 16.7278 9.30312 17.7124 10.2877C18.6969 11.2723 19.2501 12.6076 19.2501 14C19.2501 15.3924 18.6969 16.7277 17.7124 17.7123C16.7278 18.6969 15.3925 19.25 14.0001 19.25C12.6077 19.25 11.2723 18.6969 10.2878 17.7123C9.30319 16.7277 8.75007 15.3924 8.75007 14C8.75007 12.6076 9.30319 11.2723 10.2878 10.2877C11.2723 9.30312 12.6077 8.75 14.0001 8.75ZM14.0001 10.5C13.0718 10.5 12.1816 10.8687 11.5252 11.5251C10.8688 12.1815 10.5001 13.0717 10.5001 14C10.5001 14.9283 10.8688 15.8185 11.5252 16.4749C12.1816 17.1313 13.0718 17.5 14.0001 17.5C14.9283 17.5 15.8186 17.1313 16.4749 16.4749C17.1313 15.8185 17.5001 14.9283 17.5001 14C17.5001 13.0717 17.1313 12.1815 16.4749 11.5251C15.8186 10.8687 14.9283 10.5 14.0001 10.5Z" fill='.$colorty.'/>
            </svg>' ,
            'articles' =>'<svg width="22" height="18" viewBox="0 0 22 18" fill='.$colorty.' xmlns="http://www.w3.org/2000/svg">
            <path d="M15.6667 17.1667H0.5V14.8333H15.6667V17.1667ZM21.5 5.5H0.5V7.83333H21.5V5.5ZM0.5 0.833328V3.16666H13.3333V0.833328H0.5ZM16.8333 0.833328V3.16666H21.5V0.833328H16.8333ZM9.83333 10.1667V12.5H21.5V10.1667H9.83333ZM0.5 10.1667V12.5H6.33333V10.1667H0.5Z" fill="#2D4291"/>
            </svg>' ,
            'sites' =>'<svg width="26" height="22" viewBox="0 0 26 22" fill='.$colorty.' xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.3337 5.16667C15.0242 5.16667 14.7275 5.28959 14.5087 5.50838C14.2899 5.72717 14.167 6.02392 14.167 6.33334V15.6667C14.167 15.9761 14.2899 16.2728 14.5087 16.4916C14.7275 16.7104 15.0242 16.8333 15.3337 16.8333H20.0003C20.3097 16.8333 20.6065 16.7104 20.8253 16.4916C21.0441 16.2728 21.167 15.9761 21.167 15.6667V6.33334C21.167 6.02392 21.0441 5.72717 20.8253 5.50838C20.6065 5.28959 20.3097 5.16667 20.0003 5.16667H15.3337ZM18.8337 7.50001H16.5003V14.5H18.8337V7.50001Z" fill="#2D4291"/>
            <path d="M6.00065 5.16667C5.69123 5.16667 5.39449 5.28959 5.17569 5.50838C4.9569 5.72717 4.83398 6.02392 4.83398 6.33334C4.83398 6.64276 4.9569 6.9395 5.17569 7.1583C5.39449 7.37709 5.69123 7.50001 6.00065 7.50001H10.6673C10.9767 7.50001 11.2735 7.37709 11.4923 7.1583C11.7111 6.9395 11.834 6.64276 11.834 6.33334C11.834 6.02392 11.7111 5.72717 11.4923 5.50838C11.2735 5.28959 10.9767 5.16667 10.6673 5.16667H6.00065ZM6.00065 9.83334C5.69123 9.83334 5.39449 9.95626 5.17569 10.175C4.9569 10.3938 4.83398 10.6906 4.83398 11C4.83398 11.3094 4.9569 11.6062 5.17569 11.825C5.39449 12.0438 5.69123 12.1667 6.00065 12.1667H10.6673C10.9767 12.1667 11.2735 12.0438 11.4923 11.825C11.7111 11.6062 11.834 11.3094 11.834 11C11.834 10.6906 11.7111 10.3938 11.4923 10.175C11.2735 9.95626 10.9767 9.83334 10.6673 9.83334H6.00065ZM4.83398 15.6667C4.83398 15.3573 4.9569 15.0605 5.17569 14.8417C5.39449 14.6229 5.69123 14.5 6.00065 14.5H10.6673C10.9767 14.5 11.2735 14.6229 11.4923 14.8417C11.7111 15.0605 11.834 15.3573 11.834 15.6667C11.834 15.9761 11.7111 16.2728 11.4923 16.4916C11.2735 16.7104 10.9767 16.8333 10.6673 16.8333H6.00065C5.69123 16.8333 5.39449 16.7104 5.17569 16.4916C4.9569 16.2728 4.83398 15.9761 4.83398 15.6667Z" fill="#2D4291"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.66699 0.5C2.73873 0.5 1.8485 0.868749 1.19212 1.52513C0.535741 2.1815 0.166992 3.07174 0.166992 4V18C0.166992 18.9283 0.535741 19.8185 1.19212 20.4749C1.8485 21.1313 2.73873 21.5 3.66699 21.5H22.3337C23.2619 21.5 24.1522 21.1313 24.8085 20.4749C25.4649 19.8185 25.8337 18.9283 25.8337 18V4C25.8337 3.07174 25.4649 2.1815 24.8085 1.52513C24.1522 0.868749 23.2619 0.5 22.3337 0.5H3.66699ZM22.3337 2.83333H3.66699C3.35757 2.83333 3.06083 2.95625 2.84203 3.17504C2.62324 3.39383 2.50033 3.69058 2.50033 4V18C2.50033 18.3094 2.62324 18.6062 2.84203 18.825C3.06083 19.0438 3.35757 19.1667 3.66699 19.1667H22.3337C22.6431 19.1667 22.9398 19.0438 23.1586 18.825C23.3774 18.6062 23.5003 18.3094 23.5003 18V4C23.5003 3.69058 23.3774 3.39383 23.1586 3.17504C22.9398 2.95625 22.6431 2.83333 22.3337 2.83333Z" fill="#2D4291"/>
            </svg>
            ' ,
        ];

        return $icons->$kind;
    }
}