// config.js
// Contains global variables, configuration, and simple utility functions.

// ----------------------------------------------------
// 0. GLOBAL VARS & CONFIG
// ----------------------------------------------------
let auth;
let db;
let currentUser = null;
let currentProfileData = {};
let allUsersCache = [];
let requestCache = {};
let currentChatId = null;
let chatListener = null;
let currentChatPartnerId = null; 

const DEFAULT_AVATAR = "https://cdn-icons-png.flaticon.com/512/149/149071.png";

// --- Firebase Configuration ---
// IMPORTANT: REPLACE WITH YOUR OWN FIREBASE CONFIGURATION
const firebaseConfig = {
    apiKey: "AIzaSyDVHxatohLvJNqIHXjf1ZXdmmWX5W1EpNw",
    authDomain: "duoup-cfae6.firebaseapp.com",
    projectId: "duoup-cfae6",
    messagingSenderId: "812263060524",
    appId: "1:812263060524:web:ac09c3ae610db1cd110d89",
    measurementId: "G-46B67F90FK"
};

// UIDs of Co-Founders for special badges
const CO_FOUNDER_UIDS = [
    "GmH4xJp92vY8sbwIWgJWjFw9MOQ2", // Example UID 1
];

// Custom Badges Mapping
const CUSTOM_BADGE_MAP = {
    "kHsYFJVHCGfQzI16nJH0mg1rv7u1": { title: "Pilex6", color: "pilex6" }, 
};

// ---------------------------
// Utility Functions
// ---------------------------

function getCustomBadge(uid) {
    const customData = CUSTOM_BADGE_MAP[uid];
    if (customData) {
        const style = `
            background: linear-gradient(45deg, #00ff00, #009900); 
            color: #0b0b0b; 
            font-size: 0.75em; 
            padding: 2px 8px; 
            border-radius: 4px; 
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 8px;
            box-shadow: 0 0 8px #00ff00;
        `;
        return `<div style="${style}">${customData.title}</div>`;
    }
    return '';
}

function getCoFounderBadge(uid) {
    if (CO_FOUNDER_UIDS.includes(uid)) {
        const style = `
            background: linear-gradient(90deg, #00ffff, #ff00ff); 
            color: #111; 
            font-size: 0.75em; 
            padding: 2px 8px; 
            border-radius: 4px; 
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 8px; 
            box-shadow: 0 0 8px #00ffff;
        `;
        return `<div style="${style}">⚡ DUO UP DEV</div>`;
    }
    return '';
}

function getChatId(otherUid) {
    return [currentUser.uid, otherUid].sort().join('_');
}

function getCanonicalId(uid1, uid2) {
    return uid1 < uid2 ? uid1 + "_" + uid2 : uid2 + "_" + uid1;
}

function findUserInCache(uid) {
    return allUsersCache.find(user => user.id === uid);
}
