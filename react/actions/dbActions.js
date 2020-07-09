import dbActionTypes from 'constants/dbActionTypes';

export const connect = () =>
{
  return {
      type: dbActionTypes.CLICK_DATABASE_CONNECT
    }
}

export const disconnect = () =>
{
  return {
      type: dbActionTypes.CLICK_DATABASE_DISCONNECT
    }
}
