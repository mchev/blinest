export const replaceEmojis = (text) => {
  return Object.keys(emojiMapping).reduce((updatedText, emojiCode) => {
    const emoji = emojiMapping[emojiCode];
    const regex = new RegExp(escapeRegExp(emojiCode), 'g');
    return updatedText.replace(regex, emoji);
  }, text);
};

const escapeRegExp = (string) => {
  return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
};

const emojiMapping = {
  ':thumbsup:': '👍',
  ':thumbsdown:': '👎',
  ':heart:': '❤️',
  ':fire:': '🔥',
  ':ok_hand:': '👌',
  ':clap:': '👏',
  ':muscle:': '💪',
  ':pray:': '🙏',
  ':100:': '💯',
  ':sunglasses:': '😎',
  ':joy:': '😂',
  ':sweat_smile:': '😅',
  ':thinking_face:': '🤔',
  ':heart_eyes:': '😍',
  ':raised_hands:': '🙌',
  ':rose:': '🌹',
  ':star:': '⭐',
  ':tada:': '🎉',
  ':rocket:': '🚀',
  ':unicorn_face:': '🦄',
  ':musical_note:': '🎵',
  ':headphones:': '🎧',
  ':guitar:': '🎸',
  ':microphone:': '🎤',
  ':violin:': '🎻',
  ':trumpet:': '🎺',
  ':saxophone:': '🎷',
  ':drum:': '🥁',
  ':notes:': '🎶',
  ':piano:': '🎹',
  ':)': '😊',
  ':D': '😃',
  ':(': '😞',
  ';)': '😉',
  ':p': '😛',
  ':O': '😲',
  '<3': '❤️',
  ':*': '😘',
  ':/': '😕',
  ':$': '🤑',
};