import databaseActionTypes from 'constants/databaseActionTypes';

export const connect = () =>
{
  return {
      type: databaseActionTypes.CLICK_DATABASE_CONNECT
    }
}

export const disconnect = () =>
{
  return {
      type: databaseActionTypes.CLICK_DATABASE_DISCONNECT
    }
}
