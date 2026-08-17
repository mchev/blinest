/**
 * Translation keys for wrong-answer feedback (positive, non-aggressive).
 * One is picked at random when the user gives a wrong answer.
 */
export const WRONG_MESSAGE_KEYS = ['Not quite!', 'Almost!', 'Good try!', 'Next time!', 'Keep going!', 'No worries!', 'Close one!', 'Not this time!', "You'll get the next one!"]

export function pickWrongMessageKey() {
  return WRONG_MESSAGE_KEYS[Math.floor(Math.random() * WRONG_MESSAGE_KEYS.length)]
}
